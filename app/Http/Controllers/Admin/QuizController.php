<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index() { return view('admin.quizzes.index', ['quizzes' => []]); }
    public function create() { return view('admin.quizzes.create'); }
    public function store(Request $request) { return redirect()->route('admin.quizzes.index'); }
    public function edit($quiz) { return view('admin.quizzes.edit'); }
    public function update(Request $request, $quiz) { return redirect()->route('admin.quizzes.index'); }
    public function destroy($quiz) { return redirect()->route('admin.quizzes.index'); }
}
