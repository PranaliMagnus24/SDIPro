<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class UserController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::latest()->paginate(50);
  
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 50);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'nullable|email|unique:users,email|required_without:mobile',
        'mobile' => 'nullable|string|digits:10|unique:users,mobile|required_without:email',
        'password' => 'required|same:confirm-password',
        'roles' => 'required'
    ]);

    $input = $request->all();
    $input['password'] = Hash::make($input['password']);

    $user = User::create($input);
    $user->assignRole($request->input('roles'));

    return redirect()->route('users.index')
                    ->with('success', 'User created successfully');
}
    
public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string|min:6',
    ]);

    $loginInput = $request->input('username');

    // Determine if input is email or mobile
    $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

    // Check if user exists
    $userExists = User::where($loginField, $loginInput)->exists();

    if (!$userExists) {
        return back()->withErrors([
            'login' => 'No account found with this Email or Mobile Number.',
        ])->withInput();
    }

    // Attempt login
    if (Auth::attempt([$loginField => $loginInput, 'password1' => $request->password], $request->remember)) {
        return redirect()->intended(route('home'));
    }

    return back()->withErrors([
        'login' => 'Invalid credentials. Please check your Email/Mobile and Password.',
    ])->withInput();
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'login' => 'required|string|unique:users,email,'.$id.'|unique:users,mobile,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}