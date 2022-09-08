<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::insert([
            [
                'project_name'   => 'WMS',
                'client_id'      =>  1,
                'project_start'  => '2022-07-28',
                'project_end'    => '2022-08-28',
                'project_status' => 'OPEN',
            ],
            [
                'project_name'   => 'FILMS',
                'client_id'      => 2,
                'project_start'  => '2022-06-01',
                'project_end'    => '2022-08-31',
                'project_status' => 'DOING',
            ],
            [
                'project_name'   => 'DOC',
                'client_id'      => 2,
                'project_start'  => '2022-01-01',
                'project_end'    => '2022-04-30',
                'project_status' => 'DONE',
            ],
            [
                'project_name'   => 'POS',
                'client_id'      => 3,
                'project_start'  => '2022-05-01',
                'project_end'    => '2022-08-31',
                'project_status' => 'DOING',
            ],

         ]);
    }
}
