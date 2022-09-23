<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Auth;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;

class UsersManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usermodel = new User;
        $users = $usermodel->allUsers();

        $roles = Role::all();

        return View('usersmanagement.show-users', compact('users', 'roles'));
    }

    public function list(Request $request)
    {
        $currentUser = Auth::user();
        $limit = $request->length;
        $offset = $request->start;
        $search = $request->search['value'];
        $order = $request->order[0]['column'];
        $adod = $request->order[0]['dir'];
        $oby = "id";
        // echo FacadesAuth::user()->id;
        // print_r(User::find(FacadesAuth::user()->id)->isAdmin());

        $totalusers = DB::table('users')->where([['id','!=', $currentUser->id],['deleted_at','=', NULL]])->count();

        $totfil = $totrec = $totalusers;

        if (!empty($search)) {
            $totalUserSearch = User::with('roles')
                                ->when(!empty($search), function($query) use ($search){
                                $query->where('name', 'LIKE', '%' . $search . '%')
                                ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere('phone', 'like', '%' . $search . '%')
                                ->orWhere('job_title', 'like', '%' . $search . '%')
                                ->orWhere('address', 'like', '%' . $search . '%')
                                ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $search . '%');
                                })
                            ->where('id','!=', $currentUser->id)
                            ->where('deleted_at','=', NULL)
                            ->select('*')
                            ->count();
            $totfil = $totalUserSearch; #TOTAL FILTERED RECORDS
        }

        $listOfUsers = User::with('roles')
        ->when(!empty($search), function($query) use ($search){
        $query->where('name', 'LIKE', '%' . $search . '%')
        ->orWhere('first_name', 'LIKE', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')
        ->orWhere('email', 'like', '%' . $search . '%')
        ->orWhere('phone', 'like', '%' . $search . '%')
        ->orWhere('job_title', 'like', '%' . $search . '%')
        ->orWhere('address', 'like', '%' . $search . '%')
        ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $search . '%');
    })
    ->where('id','!=', $currentUser->id)
    ->where('deleted_at','=', NULL)
    ->select('*')
    ->orderBy($oby, $adod)->skip($offset)->take($limit)->get();

    $adata['recordsTotal'] = $totrec; #TOTAL RECORDS
    $adata['recordsFiltered'] = $totfil; #TOTAL FILTERED RECORDS
    if(!empty($listOfUsers)){
        $lou = $listOfUsers;
        /* prepare html of listing - datatable */
        for ($i = 0; $i < count($lou); $i++) {

            $address = json_decode($lou[$i]->address); //print_r($address);

            $dataArray = array(
                ($offset + $i) + 1,
                '<a href="/users/'.$lou[$i]->id.'">'.$lou[$i]->name.'</a>',
                $lou[$i]->roles[0]['name'],
                $lou[$i]->email,
                $lou[$i]->walletid,
                $lou[$i]->first_name,
                $lou[$i]->last_name,
                $lou[$i]->phone,
                $lou[$i]->job_title,
                $address->street.'<br>'.$address->zip,
                date('d-m-Y', strtotime($lou[$i]->created_at)),

                '<div class="btnGroup"><form method="post" class="delete-btn-form" action="/users/'.$lou[$i]->id.'" >
                '.csrf_field().method_field('DELETE').'
                <button data-toggle="modal" data-target="#confirmDelete" data-title="Delete User" data-message="Are you sure you want to delete this user?" type="button" class="btn btn-danger btn-sm"><i class="fa fa-user-times"></i></button>
                '.View('modals.modal-delete').'</form>
                <a class="btn btn-sm btn-info btn-block edit-btn" href="users/'. $lou[$i]->id .'/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-user-edit"></i></a></div>'.View('scripts.delete-modal-script')
            );

            $adata['data'][] = $dataArray;
        }
    }

    if($totrec == 0 || $totfil == 0){
        $adata['data'] = array();
    }

    return  json_encode($adata);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('slug','subadmin')->get();

        return view('usersmanagement.create-user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'                  => 'required|max:255|unique:users|alpha_dash',
                'first_name'            => 'alpha_dash',
                'last_name'             => 'alpha_dash',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'role'                  => 'required',
                'phone_number'          => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'job_title'             => 'required',
                'address'               => 'required'
            ],
            [
                'name.unique'         => trans('auth.userNameTaken'),
                'name.required'       => trans('auth.userNameRequired'),
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required'  => trans('auth.lNameRequired'),
                'email.required'      => trans('auth.emailRequired'),
                'email.email'         => trans('auth.emailInvalid'),
                'password.required'   => trans('auth.passwordRequired'),
                'password.min'        => trans('auth.PasswordMin'),
                'password.max'        => trans('auth.PasswordMax'),
                'role.required'       => trans('auth.roleRequired'),
                'phone_number.required' => trans('auth.phonenumRequired'),
                'job_title.required'    => trans('auth.JobTitleRequired'),
                'address.required'    => trans('auth.AddressRequired'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();
        $address = json_encode(array("street"=>$request->input('address'),"city"=>$request->input('city'),"state"=>$request->input('state'),"zip"=>$request->input('zip'),"country"=>$request->input('country')));

        $user = User::create([
            'name'             => strip_tags($request->input('name')),
            'first_name'       => strip_tags($request->input('first_name')),
            'last_name'        => strip_tags($request->input('last_name')),
            'email'            => $request->input('email'),
            'password'         => Hash::make($request->input('password')),
            'phone'            => $request->input('phone_number'),
            'job_title'        => $request->input('job_title'),
            'address'          => $address,
            'token'            => str_random(64),
            'admin_ip_address' => $ipAddress->getClientIp(),
            'activated'        => 1,
        ]);

        $user->profile()->save($profile);
        $user->attachRole($request->input('role'));
        $user->save();
        return redirect()->route('users.edit', $user->id)->with('success', 'User Created Successfully. You can update profile picture here');
        // return redirect('users')->with('success', trans('usersmanagement.createSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('usersmanagement.show-user', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        foreach ($user->roles as $userRole) {
            $currentRole = $userRole;
        }

        $data = [
            'user'        => $user,
            'roles'       => $roles,
            'currentRole' => $currentRole,
        ];

        return view('usersmanagement.edit-user')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User                     $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $emailCheck = ($request->input('email') !== '') && ($request->input('email') !== $user->email);
        $ipAddress = new CaptureIpTrait();

        if ($emailCheck) {
            $validator = Validator::make($request->all(), [
                'name'                  => 'required|max:255|unique:users|alpha_dash',
                'first_name'            => 'alpha_dash',
                'last_name'             => 'alpha_dash',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'nullable|min:6|confirmed',
                'password_confirmation' => 'same:password',
                'role'                  => 'required',
                'phone_number'          => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'job_title'             => 'required',
                'address'               => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|max:255|alpha_dash|unique:users,name,'.$user->id,
                'first_name'    => 'alpha_dash',
                'last_name'     => 'alpha_dash',
                'password'      => 'nullable|confirmed|min:6',
                'password_confirmation' => 'same:password',
                'role'                  => 'required',
                'phone_number'          => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'job_title'             => 'required',
                'address'               => 'required'
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = strip_tags($request->input('name'));
        $user->first_name = strip_tags($request->input('first_name'));
        $user->last_name = strip_tags($request->input('last_name'));

        $user->phone = strip_tags($request->input('phone_number'));
        $user->job_title = strip_tags($request->input('job_title'));

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        if ($request->input('password') !== null) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->address = json_encode(array("street"=>$request->input('address'),"city"=>$request->input('city'),"state"=>$request->input('state'),"zip"=>$request->input('zip'),"country"=>$request->input('country')));

        $userRole = $request->input('role');
        if ($userRole !== null) {
            $user->detachAllRoles();
            $user->attachRole($userRole);
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        switch ($userRole) {
            case 3:
                $user->activated = 0;
                break;

            default:
                $user->activated = 1;
                break;
        }

        $user->save();

        return back()->with('success', trans('usersmanagement.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        $ipAddress = new CaptureIpTrait();

        if ($user->id !== $currentUser->id) {
            $user->deleted_ip_address = $ipAddress->getClientIp();
            $user->save();
            $user->delete();
            return redirect('/users')->with('success', trans('usersmanagement.deleteSuccess'));
        }

        return back()->with('error', trans('usersmanagement.deleteSelfError'));
    }

    /**
     * Method to search the users.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('user_search_box');
        $searchRules = [
            'user_search_box' => 'required|string|max:255',
        ];
        $searchMessages = [
            'user_search_box.required' => 'Search term is required',
            'user_search_box.string'   => 'Search term has invalid characters',
            'user_search_box.max'      => 'Search term has too many characters - 255 allowed',
        ];

        $validator = Validator::make($request->all(), $searchRules, $searchMessages);

        if ($validator->fails()) {
            return response()->json([
                json_encode($validator),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $results = User::where('id', 'like', $searchTerm.'%')
                            ->orWhere('name', 'like', $searchTerm.'%')
                            ->orWhere('email', 'like', $searchTerm.'%')->get();

        // Attach roles to results
        foreach ($results as $result) {
            $roles = [
                'roles' => $result->roles,
            ];
            $result->push($roles);
        }

        return response()->json([
            json_encode($results),
        ], Response::HTTP_OK);
    }


    /**
     * Upload and Update user avatar.
     *
     * @param $file
     *
     * @return mixed
     */
    public function upload(Request $request, $id)
    {
        if ($request->hasFile('file')) {

            $avatar = $request->file('file');
            $filename = 'avatar.'.$avatar->getClientOriginalExtension();
            $save_path = storage_path().'/users/id/'.$id.'/uploads/images/avatar/';
            $path = $save_path.$filename;
            $public_path = '/images/profile/'.$id.'/avatar/'.$filename;

            // Make the user a folder and set permissions
            File::makeDirectory($save_path, $mode = 0755, true, true);

            // Save the file to the server
            Image::make($avatar)->resize(300, 300)->save($save_path.$filename);

            $currentUser = User::find($id);
            // print_r($currentUser);

            // Save the public image path
            $currentUser->profile->avatar = $public_path;
            $currentUser->profile->save();

            return response()->json(['path' => $path], 200);
        } else {
            return response()->json(false, 200);
        }
    }
}
