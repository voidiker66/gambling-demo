<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCalculateDistanceFunction extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE FUNCTION calculate_distance(lat1 FLOAT, lon1 FLOAT, lat2 FLOAT, lon2 FLOAT) RETURNS FLOAT
            DETERMINISTIC
            BEGIN
                DECLARE dlat FLOAT;
                DECLARE dlon FLOAT;
                DECLARE a FLOAT;
                DECLARE c FLOAT;
                DECLARE distance FLOAT;

                -- Convert degrees to radians
                SET lat1 = RADIANS(lat1);
                SET lon1 = RADIANS(lon1);
                SET lat2 = RADIANS(lat2);
                SET lon2 = RADIANS(lon2);

                -- Haversine formula
                SET dlat = lat2 - lat1;
                SET dlon = lon2 - lon1;
                SET a = SIN(dlat / 2) * SIN(dlat / 2) + COS(lat1) * COS(lat2) * SIN(dlon / 2) * SIN(dlon / 2);
                SET c = 2 * ATAN2(SQRT(a), SQRT(1 - a));
                SET distance = 6371 * c;  -- Earth radius in kilometers

                RETURN distance;
            END
        ");
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION IF EXISTS calculate_distance");
    }
}
