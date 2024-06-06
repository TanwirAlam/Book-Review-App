<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use Illuminate\Support\Facades\File;
/*Image Thumbnail Driver */
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    /* This Method show , Display All Books List Page */
    public function index(Request $request){

        $books=Book::orderBy('created_at','DESC');
        if(!empty($request->keyword)){
            $books->where('title','like','%'.$request->keyword.'%');
        }
        $books=$books->paginate(10);
        //dd($books);
        return view('books.list',compact('books'));
    }
    /*This Method show Add Books Form */
    public function create(){
        return view('books.add');
    }
    /*This Method show , Save Books function */
    public function store(Request $request){

        $rules=[
            'title' =>'required|min:10',
            'author' =>'required',
            'description'=>'required',
            'status'=>'required',
        ];
        if($request->book_image!=''){
            $rules['image']='image';
        }
        $validator=Validator::make($request->all(),$rules);
       if($validator->fails()){
           return redirect()->route('books.add')->withInput()->withErrors($validator);
       }
       if($request->book_image!=''){
            $imageName = time().'.'.$request->book_image->extension();  
            $request->book_image->storeAs('public/books', $imageName);
        }else{
            $imageName='';
        }

       $book=new Book;
       $book->title=$request->title;
       $book->author=$request->author;
       $book->description=$request->description;
       $book->status=$request->status;
       $book->image=$imageName;
       $book->save();

        // create new image thumb
        $manager = new ImageManager(Driver::class);
        $img = $manager->read('storage/books/'.$imageName); // 800 x 600
        $img->cover(200,200);
        $img->save('storage/books/thumb/'.$imageName);

       return redirect()->route('books.index')->with('success','Your Book added successfully');

    }

    /*This Method show Edit Books function */
    public function edit($id){
        $book=Book::find($id);
        return view('books.update',compact('book'));
    }
    /*This Method show Update Books function */
    public function update(Request $request,$id ){
        $rules=[
            'title' =>'required|min:10',
            'author' =>'required',
            'description'=>'required',
            'status'=>'required',
        ];
        if($request->book_image!=''){
            $rules['image']='image';
        }
        $validator=Validator::make($request->all(),$rules);
       if($validator->fails()){
           return redirect()->route('books.edit',$id)->withInput()->withErrors($validator);
       }
       $book=Book::find($id);
       if($request->book_image!=''){
            /* Delete Old File */
            File::delete('storage/books/'.$book->image);
            File::delete('storage/books/thumb/'.$book->image);

            $imageName = time().'.'.$request->book_image->extension();  
            $request->book_image->storeAs('public/books', $imageName);

            $manager = new ImageManager(Driver::class);
            $img = $manager->read('storage/books/'.$imageName); // 800 x 600
            $img->cover(200,200);
            $img->save('storage/books/thumb/'.$imageName);

        }else{
            $imageName=$book->image;
        }

      
       $book->title=$request->title;
       $book->author=$request->author;
       $book->description=$request->description;
       $book->status=$request->status;
       $book->image=$imageName;
       $book->save();

        // create new image thumb
       

       return redirect()->route('books.index')->with('success','Your Book added successfully');
    }
    /*This Method show Delete Books function */
    public function destroy(Request $request){
         $book=Book::find($request->id);
         if($book==null){
            session()->flash('error','Book not found');
            return response()->json([
                 'status' =>false,
                 'message' =>'Book not found'
            ]);
         }else{
           
            File::delete('storage/books/'.$book->image);
            File::delete('storage/books/thumb/'.$book->image);
            $book->delete();
            session()->flash('success','Book deleted successfully');
            return response()->json([
                'status' =>true,
                'message' =>'Book deleted successfully '
           ]);
         }
    }
}
