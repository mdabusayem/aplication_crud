<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ApplicantRequest;
use App\Http\Resources\ApplicantResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the applicants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // Redis::del('applicants');
        $check_redis = Redis::get('applicants');
        
        if(isset($check_redis)) {
            $applicants = json_decode($check_redis, FALSE);
        }else {
            $applicants = Applicant::orderBy('id','desc')->get();
            Redis::set('applicants', $applicants);
        }
        //dd($applicants);
        return response()->json(['applicants' => $applicants]);
    }

    /**
     * Show the form for creating a new applicant.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Show the applicant creation form
        return view('applicants.create');
    }

    /**
     * Store a newly created applicant in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApplicantRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if ($request->hasFile('image')) {
                $file_name = time().'_'.$request->file('image')->getClientOriginalName();
                $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
                $validatedData['image'] = $file_name;
            }
            $applicant = Applicant::create($validatedData);
            if($applicant) {
                Redis::del('applicants');
            }
            return response()->json(['message' => 'Applicant created successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified applicant.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Retrieve the applicant by ID and show the edit form
        $applicant = Applicant::findOrFail($id);
       // var_dump($applicant->skills);
        
        //return view('applicants.edit', ['applicant' => $applicant]);
        return response()->json(['applicant' => $applicant]);
    }

    /**
     * Update the specified applicant in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $applicant = Applicant::findOrFail($id);
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female',
            'skills' => 'required|array',
        ];
        $validatedData = $request->validate($rules);
        if ($request->file('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif',
            ]);
            $file_name = time().'_'.$request->file('image')->getClientOriginalName();
            $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
            $validatedData['image'] = $file_name;

            \Storage::disk('public')->delete('/storage/uploads/'.$applicant->image);
        }
        else{
            $validatedData['image'] = $applicant->image;
        }
        $applicant->update($validatedData);
        
        $applicants=collect(json_decode(Redis::get('applicants')));
        $apply=$applicants->where('id',$id)->first();
        $apply->name=$request->name;
        $apply->email=$request->email;
        $apply->gender=$request->gender;
        $apply->skills=$request->skills;
        $apply->image=$validatedData['image'];
        $index=$applicants->search($apply);
        $applicants[$index]=$apply;
        Redis::set('applicants',json_encode($applicants));


        return response()->json(['message' => 'Applicant updated successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    /**
     * Remove the specified applicant from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the applicant by ID and soft delete
        //dd($id);
        $applicant = Applicant::findOrFail($id);
        
        $image = public_path('/storage/uploads/').$applicant->image;
            if(file_exists($image)){
                \Storage::disk('public')->delete('/storage/uploads/'.$applicant->image);
            }
        
        $applicant->delete();

        $applicants=collect(json_decode(Redis::get('applicants')));
        $apply=$applicants->where('id',$id)->first();
        $index=$applicants->search($apply);
        
        //$applicants->forget($index);
        $applicants->splice($index, 1);
        Redis::set('applicants',json_encode($applicants));
        

        // Redirect to the list of applicants or show a success message
        //return redirect('/applicants')->with('success', 'Applicant deleted successfully');
        return response()->json(['message' => 'Applicant deleted successfully']);
    }
}