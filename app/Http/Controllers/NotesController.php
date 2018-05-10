<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class NotesController extends Controller
{
	public function index()
	{
	    $notes = \App\Note::all();
	 	
	    return view('notes.index', compact('notes'));
	}

	public function destroy($id)
	{

	    $user = User::find($id);

	    dd($user);

	}
}
