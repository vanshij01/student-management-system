<?php

namespace App\Repositories;

use App\Contract\CourseRepositoryInterface;
use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
    public function getAll()
    {
        return Course::orderBy("id", "desc")->whereStatus(1)->get();
    }

    public function courseData()
    {
        return Course::orderBy("id", "desc")->whereStatus(1)->get();
    }

    public function getById($id)
    {
        return $id === ""  ? Course::all() : Course::findOrFail($id);
    }

    public function create($params)
    {
        return Course::create($params);
    }

    public function update($payLoad, $id)
    {
        $course = Course::findOrFail($id);
        return $course->update($payLoad);
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        return $course->delete();
    }

    public function isExist($name, $email, $number)
    {
        return Course::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function getCoursesByEducation($education_type)
    {
        return Course::where('education_type', $education_type)->orderBy("id", "desc")->whereStatus(1)->get();
    }
}
