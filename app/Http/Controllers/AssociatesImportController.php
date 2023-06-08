<?php

namespace App\Http\Controllers;

use App\Models\Associates;
use App\Http\Utils\CsvFileHandler;
use App\Http\Utils\JsonFileHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AssociatesImportController extends Controller
{
    /**
     * Import associates from a file input
     */
    public function fileImport(Request $request)
    {
        // Ensure request has associateInputFile, isn't null, and is csv/json
        $request->validate([
            'associateInputFile' => 'required|file|mimetypes:application/json,text/csv'
        ]);

        $file = $request->file('associateInputFile');

        $handler = null;
        if ($file->getMimeType() === 'application/json') {
            // handle json files
            $handler = new JsonFileHandler($file);
        } elseif ($file->getMimeType() === 'text/csv') {
            // handle csv files
            $handler = new CsvFileHandler($file);
        } else {
            // Should never occur - handled in request validation
            throw new \Exception("File type not supported.");
        }

        // iterate each associate and import
        $handler->map(function ($associate) {
            try {
                return Associates::updateOrCreate([
                    'affiliate_id' => $associate->affiliate_id,
                    'name' => $associate->name,
                    'longitude' => $associate->longitude,
                    'latitude' => $associate->latitude
                ]);
            } catch (\Exception $e) {
                // TODO add error logging
                return $e->getMessage();
            }
        });

        // Redirect to associates list page
        return redirect('/associates/list');
    }
}