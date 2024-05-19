# FileManager Plugin for Laravel
## Overview
The FileManager plugin is a custom Laravel solution designed to simplify file management. It handles file uploads, updates, deletions, and retrievals while maintaining references in a database table. This plugin leverages Laravel's built-in filesystem and database functionalities to offer a robust and easy-to-use file management system.

## Features
* Upload files: **Store files and record their references in a database.**
* Update files: **Store files and record their references in a database.**
* Delete files: **Store files and record their references in a database.**
* Retrieve file paths: **Store files and record their references in a database.**

## Installation
## Step 1: Installation Command
You can install this plugin by following command : 
```php
composer require file/repo
```
## Step 2: Register Service Provider
Add the service provider to `config/app.php`:
```php
'providers' => [
    // Other Service Providers
    file\repo\RepoServiceProvider::class,
],
```
## Step 3: Run Migration
The service provider will automatically generate a migration for the file_repos table when the application boots. Run the migration using:
```php
php artisan migrate
```
# Usage

## Upload Files

To upload a file, use the upload method. This stores the file and creates a record in the `file_repos` table.
```php
use file\repo\FileManager;

FileManager::upload('users', $userId, $requestuest->file('profile_picture'));
```

## Update Files

To update an existing file, use the `update` method. This replaces the old file with a new one and updates the record in the database.
```php
FileManager::update('users', $id, $requestuest->file('new_profile_picture'));
```

## Delete Files

To delete a file and its record from the database, use the `deletefile` method.
```php
FileManager::deletefile($fileId);
```
## Retrieve File Paths

To retrieve all file paths associated with a specific reference table and ID, use the `get_path_by` method.
```php
$paths = FileManager::get_path_by('users', $userId);
```

# Example: User Profile Picture Management

## Step 1: File Upload
Handle the file upload when a Product upload.

```php
use Illuminate\Http\Request;
use file\repo\FileManager;
use App\Models\Product;

class ProductController extends Controller
{
 public function create(Request $request)
    {
        $request->validate([
            'name' => 'Required',
            'price' => 'Required',
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
        if ($request->file) {
            FileManager::upload("product", $product->id, $request->file);
        }
        return back()->with('success', 'Product uploaded successfully.');
    }
}
```
## Step 2: File Update
Allow admin to update their Product Image, replacing the old one.

```php
public function update(Request $request, $id)
    {
         $request->validate([
            'name' => 'Required',
            'price' => 'Required',
        ]);
        $product = Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->update();
        if ($request->file) {
          FileManager::update('product', $id, $request->file);
        }

        return back()->with('success', 'Product updated successfully.');
    }
```
## Step 3: Retrieve File Paths
Display all images of Products.

```php
    public function getById($id)
    {
        $product = Product::find($id);
        $image = FileManager::get_path_by("product", $id);
        return view('view', compact('product','image'));
    }
```
```php
        @foreach ($image as $item)
            <img src="{{ $item['path'] }}" alt="product image">
        @endforeach
```

## Step 4: File Deletion
Provide functionality to delete a user's profile picture.

```php
public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            $images = FileManager::get_path_by("product", $id);
            foreach ($images as $image) {
              FileManager::deletefile($image->id);
            }
            $product->delete();
        }

        return back();
    }
```

# Conclusion
The FileManager plugin provides a structured and efficient way to manage file uploads in your Laravel application. By following the steps and examples outlined in this README, you can easily integrate this plugin into your project and enhance its file management capabilities.
