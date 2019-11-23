<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //\DB::enableQueryLog();
        $questions = Question::with('user')->latest()->paginate(5);
        return view('questions.index')->with('questions', $questions)->render();

        //dd(\DB::getQueryLog());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $question = new Question();

        return view('questions.create')->with('question', $question);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        //
        $request->user()->questions()->create($request->only('title', 'body'));

        return redirect()->route('questions.index')->with('success', 'Your Question Has Been Submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
        // $question = Question::findOrFail($id)->first();
        $question->increment('views');

        // $question->views = $question->views + 1;
        // $question->save();

        return view('questions.show')->with('question', $question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
        // $question = Question::findOrFail($id);

        // Authorization using Gate
        // if (\Gate::denies('update-question', $question)) {
        //    abort(403, 'Access denied'); 
        // }

        // Authorization using Policies
        $this->authorize('update', $question);

        return view('questions.edit')->with('question', $question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        //
        // $question = Question::findOrFail($id);

        // Authorization using Gate
        // if (\Gate::denies('update-question', $question)) {
        //    abort(403, 'Access denied'); 
        // }

        // Authorization using Policies
        $this->authorize('update', $question);

        $question->update($request->only('title', 'body'));

        return redirect()->route('questions.index')->with('success', 'Your Question Has Been Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
        // $question = Question::findOrFail($id);

        // Authorization using Gate
        // if (\Gate::denies('delete-question', $question)) {
        //    abort(403, 'Access denied'); 
        // }

        // Authorization using Policies
        $this->authorize('delete', $question);

        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Your Question Has Been Deleted');
    }
}
