<?php

namespace App\Http\Controllers;

use App\Models\Associates;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class AssociatesController extends Controller
{
    public function view(Request $request)
    {
        return Inertia::render('Associates/List', $this->list($request));
    }

    public function list(Request $request)
    {
        // dd($request);
        $filterColumns = [
            'name' => 'String',
            'affiliate_id' => 'Int'
        ];
        $request->validate([
            'range' => 'numeric|gt:0',
            'latitude' => 'numeric|required_with:range',
            'longitude' => 'numeric|required_with:range',
            'perPage' => 'numeric',
            'filter' => 'array',
            'sortBy' => 'string',
            'order' => 'boolean|required_with:sortBy'
        ]);
        
        $filters = $request->query('filter');
        $range = $filters['range'] ?? null;
        $latitude = $filters['latitude'] ?? null;
        $longitude = $filters['longitude'] ?? null;
        $perPage = $filters['perPage'] ?? 10;
        
        // Range filter is unique and must be done separately from regular filter
        $query = null;
        if (!empty($range)) {
            $query = Associates::inRange($range, $latitude, $longitude);
        } else {
            $query = Associates::query();
        }
        
        // Generic key/value filter for exact matches
        // TODO Allow loose matching with LIKE on varchar fields
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                // Only allow filters that match existing columns
                if (in_array($key, array_keys($filterColumns))) {
                    if ($filterColumns[$key] === 'String') {
                        $query->where($key, 'LIKE', "%$value%");
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        if ($sortBy = $request->query('sortBy')) {
            $query->orderBy($sortBy, $request->query('order') ? 'desc' : 'asc');
        }

        return $query->paginate($perPage);
    }
}