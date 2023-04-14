<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function activities(){
        return $this->hasMany(Activity::class);
    }

    public function process_active_projects_for_a_month($year,$month, $format = null){ //$format -> "array" | "objects"

        $projects_list = [];

        //get active projects for this month
        $active_projects = $this->get_month_active_projects_list($year, $month);

        //if active projects for this month have not been set,
        // then take list from previous month and use it to create new list for this month
        if( count($active_projects) > 0 ){

            $projects_list = $active_projects;

        }else{

            $previous_month_active_projects = $this->get_month_active_projects_list($year, $month-1);

            if( count($previous_month_active_projects) > 0 ){
                //create active projects for this month using previous month projects
                $active_projects_list = new ActiveProject();
                $active_projects_list->year = $year;
                $active_projects_list->month = $month;
                $active_projects_list->projects = json_encode($previous_month_active_projects);
                $active_projects_list->save();

                $projects_list = $previous_month_active_projects;

            }else{//if no list is found from last month, then create active projects list from all projects

                $projects_list = $this->create_active_projects_list_from_all_projects($year, $month);

            }

        }


        $projects = $this->create_projects_from_list_of_project_numbers($projects_list,$format);

        //dd($projects);

        return $projects;

    }


    public function get_month_active_projects_list($year,$month){

        $active_projects = [];
        $list = ActiveProject::where('year','=',$year)->where('month','=',$month)->first();

        if( isset($list->id)){
            $active_projects = json_decode($list->projects);
        }

        return $active_projects;
    }

    public function create_active_projects_list_from_all_projects($year,$month){

        $all_projects = Project::all();
        $projects_array = [];

        foreach ($all_projects as $project){
            $projects_array[] = $project->number;
        }

        $active_projects_list = new ActiveProject();
        $active_projects_list->year = $year;
        $active_projects_list->month = $month;
        $active_projects_list->projects = json_encode($projects_array);
        $active_projects_list->save();

        return $projects_array;
    }

    public function create_projects_from_list_of_project_numbers($projects_number_list, $format){

        $all_projects = Project::all();
        $required_projects = [];

        if(count($all_projects)>0){

            foreach ($all_projects as $project) {
                if( in_array($project->number, $projects_number_list)){
                    if($format == "objects"){
                        $required_projects[] = $project;
                    }else{
                        $required_projects[$project->number] = $project->name;
                    }
                }
            }

        }

        return $required_projects;

    }


}
