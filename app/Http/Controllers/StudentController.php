<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('room')) {
            return Student::where('room', $request->room)->get();
        }
        return Student::all();
    }

    public function getRooms(Request $request)
    {
        if ($request->has('floor')) {
            $floor = $request->floor;
            $rooms = Student::where('floor', $floor)->pluck('room')->unique();
            return response()->json($rooms);
        }
        return response()->json([]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|regex:/^[a-zA-Zāčēģīķļņōŗšūž\s\-]+$/',
                'surname' => 'required|string|max:255|regex:/^[a-zA-Zāčēģīķļņōŗšūž\s\-]+$/',
                'room' => 'required|string|max:255|regex:/^(1[0-2][0-8]|2[0-2][0-8]|3[0-2][0-8]|4[0-2][0-8]|5[0-2][0-8])$/',
                'floor' => 'required|string|max:255|regex:/^[1-5]$/',
                'phone' => 'required|string|size:8|regex:/^\d{8}$/',
                'email' => 'required|string|email|max:255|ends_with:@rvt.lv',
            ]);

            return Student::create($request->all());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error adding student: ' . $e->getMessage());
            return response()->json(['error' => 'Validation error: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            Log::error('Error adding student: ' . $e->getMessage());
            return response()->json(['error' => 'Error adding student: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'checkedIn' => 'required|boolean',
        ]);

        $student = Student::findOrFail($id);

        if ($request->checkedIn && $student->checkedIn) {
            return response()->json(['error' => 'Student is already checked in'], 400);
        }

        if (!$request->checkedIn && !$student->checkedIn) {
            return response()->json(['error' => 'Student is already checked out'], 400);
        }

        $student->update($request->only('checkedIn'));

        return $student;
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->noContent();
    }
}
