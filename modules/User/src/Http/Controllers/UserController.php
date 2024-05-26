<?php
namespace Modules\User\src\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\User\src\Http\Requests\UserRequest;
use Modules\User\src\Repositories\UserRepository;

class UserController extends Controller {

    protected $userRepository;
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $pageTitle = "Quản lý người dùng";
        return view('user::list', compact('pageTitle'));
    }

    public function data() {
        $users = $this->userRepository->getAllUsers();

        return DataTables::of($users)
        ->addColumn('edit', function ($users) {
            return '<a href="'.route('admin.users.edit', $users).'" class="btn btn-warning">Sửa</a>';
        })
        ->addColumn('delete', function ($users) {
            return '<a href="'.route('admin.users.delete', $users).'" class="btn btn-danger delete-action">Xóa</a>';
        })
        ->editColumn('created_at', function ($users) {
            return Carbon::parse($users->created_at)->format('d/m/Y H:i:s');
        })
        ->rawColumns(['edit', 'delete'])
        ->toJson();
    }

    public function add() {
        $pageTitle = "Thêm người dùng";
        return view('user::add', compact('pageTitle'));
    }

    public function store(UserRequest $request) {
        $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('msg', __('user::messages.add.success'));
    }

    public function edit($id) {
        $pageTitle = 'Cập nhật người dùng';
        $user = $this->userRepository->find($id);
        if (!$user) {
            abort(404);
        }

        return view('user::edit', compact('user', 'pageTitle'));
    }

    public function update(UserRequest $request, $id) {
        $data = $request->except('__token', 'password');
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $this->userRepository->update($id, $data);

        return back()->with('msg', __('user::messages.update.success'));     
    }

    public function delete($id) {
        $this->userRepository->delete($id);
        return back()->with('msg', __('user::messages.delete.success'));     
    }

}