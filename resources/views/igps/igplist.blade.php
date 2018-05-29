@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="css/igplist.css">

<?php 
    $isExecom = DB::table('positions')
        ->join('memberships', 'positions.id', '=','memberships.position_id')
        ->join('users', 'memberships.user_id', '=', 'users.id')
        ->where('users.id', Auth::user()->id)
        ->select('positions.is_execom')
        ->value('is_execom');
?>
<!-- Modal for Adding item -->
<h1 id="igp-title">INCOME-GENERATING PROJECTS</h1>
@if($isExecom)
<div id="newItem" class="newItem-box">
        <label><i class="fa fa-plus"></i><br>
                <span>Add Item</span>
        </label>
</div>

    <div class="modal-whole-div" id="modal-add-item">
        <div id="modal-add-item-content">           
            <span id="close-btn">&times;</span>
                <h2>Add Item</h2>
                    <form action="/igps" enctype="multipart/form-data" method="POST" id="newItem">
                        {{ csrf_field() }}
                        <h3>Item Name: </h3>
                        <input type="text" name="item_name" required autofocus>

                        <h3>Description:</h3>
                        <textarea name="description" cols="30" rows="" form="newItem" placeholder="Write description and/or contact details..." required autofocus></textarea>

                        <h3>Price:</h3>
                        <input type="text" name="price" required autofocus>
                        <br>

                        <h3>Add Photo</h3><br>
                        <input type="file" name="item_image" required autofocus>

                        <br><br>

                        <!-- Input member info -->
                        <input type="submit" name="submit" value="Submit" class="hover-cursor-pointer">
                    </form>
        </div>
    </div>
<!-- Added new for loop for igps -->
@endif

<!-- Modal for Viewing item -->
@forelse($igps as $igp)
    <!-- display each element -->
    <a class="hover-cursor-pointer" onclick="viewModal({{ $igp->id }})">
        <div class="item-box">
            <img src="{{ $igp->getImage($igp->doc_id) }}" width="150" height="150"></img>
            <div class="item-title">
                <label>{{ $igp->title }}</label>
                <label>Price: {{ $igp->price }}</label>
                <label><i class="fa fa-edit"></i>Edit
                <i class="fa fa-trash-o"></i>Delete</label>
                <p></p>
            </div>
        </div>
    </a>

    <!-- view content modal -->
    <div class="modal-whole-div" id="modal-view-{{ $igp->id }}">
        <div id="modal-view-item-content">
            <div id="view-div">
                <!-- <span id="close-btn">&times;</span> -->
                <div id="modal-view-image">
                    <img src="{{ $igp->getImage($igp->doc_id) }}">
                </div>
                <div id="modal-view-details">
                    <h2>{{ $igp->title }}</h2>
                    <h3>Price: {{ $igp->price }}</h3>
                    <p>{{ $igp->content }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- modal for edit -->
    <div id="modal-edit-{{ $igp->id }}" class="modal-whole-div">
        <div id="modal-div">
            <span id="close-btn">&times;</span>
            <h2>Edit IGP</h2>
            <form action="/timeline" method="POST">   
                <h3>Item Name:</h3>
                <input id="item-name" type="text" name="item-name" value="{{ $igp->title }}" required autofocus>

                <h3>Description:</h3>
                <textarea name="description" cols="30" rows="" placeholder="Write description and/or contact details...">{{ $igp->content }}</textarea>

                <h3>Price:</h3>
                <input type="text" name="price" required autofocus>
                <br>

                <h3>Edit Photo</h3><br>
                <input type="file" name="item-image" required autofocus>

                <br><br>
                <input type="submit" name="submit" value="Submit" class="hover-cursor-pointer">
            </form>
        </div>
    </div>  
    @empty
        No IGPs found.
@endforelse
<!-- JAVASCRIPT -->
   <!--  <script type="text/javascript" src="js/add-igp-modal.js"></script> -->
    <!-- OKAY na ang Add Item Modal -->

<!-- NOT OKAY: view Item modal -->
    <script type="text/javascript">
        // Add Item Modal
        var addModal = document.getElementById('modal-add-item');        
        var addBtn = document.getElementById('newItem');        
        var exit = document.getElementById("close-btn");

        addBtn.onclick = function() {
            addModal.style.display = "block";
        }

        exit.onclick = function() {
            addModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
        }   

        // View Item Modal

        var status = "none";
        var id;
        var item;
        function viewModal(el){
            id = "modal-view-"+el;
            item = document.getElementById(id);
            if(status == "none"){
                status = "block";
            }
            else{
                status = "none";
            }
            item.style.display = status;
        }

        window.onclick = function(event){
            if (event.target == item)
            {
                item.style.display = "none";
            }
        }
    </script>
@endsection