<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    /**
     * Create and submit a course with pending status.
     *
     * @param array<string, mixed> $data
     * @param string $status
     * @return Course
     */
    public function createOrSubmit(array $data, string $status = 'pending'): Course
    {
        $data['status'] = $status;
        return Course::create($data);
    }
}
