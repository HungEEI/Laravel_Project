## Cài đặt Laravel Module và Repository
### Cài đặt Laravel Module
### Cài đặt Repository cho Laravel Module
`php artisan make:module ten_module`

## Tích hợp Layout Admin
`sbadmin` `adminlte`

## Xây dựng Module quản lý User

### Tạo Migration - Seeder - Chuẩn bị giao diện
`php artisan make:seeder ten_seeder`
`php artisan db:seed --class= duong_dan`

### Tạo Repository và các phương thức cần thiết
    - Hiển thị danh sách User (có phân trang)
    - Thêm User
    - Sửa User
    - Xóa User
    - Lấy thông tin User

### Tạo FormRequest và các phương thức Validation
### Viết chức năng thêm User
### Viết chức năng hiển thị User
### Viết chức năng cập nhật User
### Viết chức năng xóa User

### Xây dựng Module quản lý danh mục
## --- Hàm tọa slug js
## --- Đệ quy đa cấp

### Xây dựng Module Khóa học 
## --- Tích hợp ckeditor và laravel filemanager

### Xây dựng Module quản lý Giảng viên
## --- Ràng buộc khóa ngoại
=> Nếu giảng viên bị xóa => Các khóa học liên quan đến giảng viên sẽ bị xóa
## --- Ràng buộc hình ảnh
*--     1 hình ảnh sử dụng nhiều nơi => xóa 1 bản ghi => xóa ảnh
*   Tạo 1 module Media (Database) => khi chọn ảnh ở các Module => bật popup của module media 

* Xây dựng Module Auth
## --- Cài đặt laravel ui bootstrap

## Hoàn thiện các câu lệnh Artisan Console
### Tạo Module
``` php artisan make:module TenModule ```

### ---  Tạo Controller
``` php artisan module:make-controller TenController TenModule ```

### ---  Tạo Request
``` php artisan module:make-request TenRequest TenModule ```

### ---  Tạo Middleware
``` php artisan module:make-middleware TenMiddleware TenModule ```

### ---  Tạo Model
``` php artisan module:make-model TenModel TenModule ```

### ---  Tạo Controller
``` php artisan module:make-controller TenController TenModule ```

### ---  Tạo Migration
``` php artisan module:make-migration TenMigration TenModule ```

### ---  Tạo Seeder
``` php artisan module:make-seeder TenSeeder TenModule ```