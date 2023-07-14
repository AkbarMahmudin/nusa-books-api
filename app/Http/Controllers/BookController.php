<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all books from the database
        $books = Book::all();

        // Return a collection of $books with pagination
        return response()->json([
            'success' => true,
            'message' => 'Book retrieved successfully.',
            'data'    => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...
        $rules = [
            'isbn'      => 'required|unique:books|max:13',
            'title'     => 'required',
            'author'    => 'required',
            'publisher' => 'required',
            'genre'     => 'required',
            'language'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data'    => $validator->errors()
            ], 400);
        }

        // Create a new book
        $book = Book::create($request->all());

        // Return a response with a book json
        return response()->json([
            'success' => true,
            'message' => 'Book created successfully.',
            'data'    => $book
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Get the book
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found.',
                'data'    => null
            ], 404);
        }

        // Return a single book
        return response()->json([
            'success' => true,
            'message' => 'Book retrieved successfully.',
            'data'    => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request...
        $rules = [
            'isbn'      => 'unique:books|max:13'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data'    => $validator->errors()
            ], 400);
        }

        // Get the book
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found.',
                'data'    => null
            ], 404);
        }

        // Update the book
        $book->update($request->all());

        // Return a response with a book json
        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully.',
            'data'    => $book
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Get the book
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found.',
                'data'    => null
            ], 404);
        }

        // Delete the book
        $book->delete();

        // Return a response with a book json
        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully.'
        ]);
    }
}
