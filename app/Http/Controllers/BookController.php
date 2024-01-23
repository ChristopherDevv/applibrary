<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Models\Book;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index()
    {
        try{
            $books = Book::latest()->get();
            return response()->json([
                'message' => 'Books list',
                'books' => $books
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error to get books',
                'error' => $e->getMessage()
            ], 400);    
        }
    }

    public function show($id)
    {
        try{
            $book = Book::find($id);
            if(!$book){
                return response()->json([
                    'message' => 'Book not found'
                ], 404);
            }
            return response()->json([
                'message' => 'Book detail',
                'book' => $book
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error to get book',
                'error' => $e->getMessage()
            ], 400);    
        }
    }

    public function store(Request $request)
    {
        try{            
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            $book = new Book();
            $book->title = $request->title;
            $book->description = $request->description;
            $book->user_id = auth()->user()->id;
            $book->save();

            return response()->json([
                'message' => 'Book created successfully',
                'book' => $book
            ], 201);


        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error to create book',
                'error' => $e->getMessage() 
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try{

        $this->authorize('update', Book::find($id));

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string|max:255',
        ]);

            $books = Book::find($id);
            if(!$books){
                return response()->json([
                    'message' => 'Book not found'
                ], 404);
            }

            $books->title = $request->title;
            $books->description = $request->description;
            $books->save();

            return response()->json([
                'message' => 'Book updated successfully',
                'book' => $books
            ], 200);

        }catch (AuthorizationException $e){
            return response()->json([
                'message' => 'You are not authorized to update this book',
                'error' => $e->getMessage() 
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Error to update book',
                'error' => $e->getMessage() 
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            $this->authorize('delete', Book::find($id));

            $book = Book::find($id);
            if(!$book){
                return response()->json([
                    'message' => 'Book not found'
                ], 404);
            }
            $book->delete();
            return response()->json([
                'message' => 'Book deleted successfully'
            ], 200);

        }catch(AuthorizationException $e){
            return response()->json([
                'message' => 'You are not authorized to delete this book',
                'error' => $e->getMessage() 
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Error to delete book',
                'error' => $e->getMessage() 
            ]);
        }
    }

    public function export()
    {
        try{
            /* $fileName = 'books.xlsx';
            $filePath = 'public/' . $fileName;
            Excel::store(new BooksExport, $filePath);

            return response()->download(storage_path('app/' . $filePath), $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true); */
            return Excel::download(new BooksExport, 'books.xlsx');
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error to export books',
                'error' => $e->getMessage() 
            ]);
        }
    }
    
}
