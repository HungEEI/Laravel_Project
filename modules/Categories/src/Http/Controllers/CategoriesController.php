<?php
namespace Modules\Categories\src\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Categories\src\Http\Requests\CategoryRequest;
use Modules\Categories\src\Repositories\CategoriesRepository;
use Modules\Categories\src\Repositories\CategoriesRepositoryInterface;

class CategoriesController extends Controller {

    protected $categoryRepository;

    public function __construct(CategoriesRepositoryInterface $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function index() {
        $pageTitle = "Quản lý Danh mục";
        return view('categories::list', compact('pageTitle'));
    }

    public function data() {
        $categories = $this->categoryRepository->getCategories();

        $categories = DataTables::of($categories)
        // ->addColumn('edit', function ($category) {
        //     return '<a href="'.route('admin.categories.edit', $category).'" class="btn btn-warning">Sửa</a>';
        // })
        // ->addColumn('delete', function ($category) {
        //     return '<a href="'.route('admin.categories.delete', $category).'" class="btn btn-danger delete-action">Xóa</a>';
        // })
        // ->addColumn('link', function ($category) {
        //     return '<a href="" class="btn btn-primary">Xem</a>';
        // })
        // ->editColumn('created_at', function ($category) {
        //     return Carbon::parse($category->created_at)->format('d/m/Y H:i:s');
        // })
        // ->rawColumns(['edit', 'delete', 'link'])
        ->toArray();

        $categories['data'] = $this->getCategoriesTable($categories['data']);

        return $categories;
    }

    public function getCategoriesTable($categories, $char = '', &$result = []) {
        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $row = $category;
                $row['name'] = $char.$row['name'];
                $row['edit'] = '<a href="'.route('admin.categories.edit', $category['id']).'" class="btn btn-warning btn-sm">Sửa</a>';
                $row['delete'] = '<a href="'.route('admin.categories.delete', $category['id']).'" class="btn btn-danger delete-action btn-sm">Xóa</a>';
                $row['link'] = '<a target="_blank" href="/danh-muc/'.$category['slug'].'" class="btn btn-primary btn-sm">Xem</a>';
                $row['created_at'] = Carbon::parse($category['created_at'])->format('d/m/Y H:i:s');
                unset($row['sub_categories']);
                unset($row['updated_at']);
                $result[] = $row;
                if (!empty($category['sub_categories'])) {                  
                    $this->getCategoriesTable($category['sub_categories'], $char.'|-- ', $result);
                }
            }
        }

        return $result;
    }

    public function add() {
        $pageTitle = "Thêm danh mục";

        $categories = $this->categoryRepository->getAllCategories()->toArray();

        return view('categories::add', compact('pageTitle', 'categories'));
    }

    public function store(CategoryRequest $request) {
        $this->categoryRepository->create([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')->with('msg', __('categories::messages.add.success'));
    }

    public function edit($id) {
        $pageTitle = 'Cập nhật danh mục';
        $categories = $this->categoryRepository->getAllCategories()->toArray();
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            abort(404);
        }

        return view('categories::edit', compact('category', 'pageTitle', 'categories'));
    }

    public function update(CategoryRequest $request, $id) {
        $data = $request->except('__token');

        $this->categoryRepository->update($id, $data);

        return back()->with('msg', __('categories::messages.update.success'));    
    }

    public function delete($id) {
        $this->categoryRepository->delete($id);
        return back()->with('msg', __('categories::messages.delete.success'));   
    }
}