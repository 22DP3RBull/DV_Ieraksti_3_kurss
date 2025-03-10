<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|ends_with:@rvt.lv',
            'checkedIn' => 'required|boolean',
        ]);

        return Student::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'checkedIn' => 'required|boolean',
        ]);

        $student = Student::findOrFail($id);
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
