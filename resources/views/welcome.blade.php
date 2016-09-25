<!doctype html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-el="http://www.w3.org/1999/xhtml"
      xmlns:v-bind="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="/css/app.css">
    <meta role="token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>

    {{--@include('partials.nav')--}}


    <div class="container-fluid" id="chat">

        <div class="row">
        <div class="col-md-1"></div>

        <div class="col-md-7">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>LaraChat</b>
            </div>

		    <div class="input-group">
                <input type="hidden" name="search_param" value="all" id="search_param">         
                <input v-model="mesej" type="text" class="form-control" name="x" placeholder="Search term...">
                <div class="input-group-btn search-panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    	<span id="search_concept">Filter by</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#contains">Contains</a></li>
                      <li><a href="#its_equal">It's equal</a></li>
                      <li><a href="#greather_than">Greather than ></a></li>
                      <li><a href="#less_than">Less than < </a></li>
                      <li class="divider"></li>
                      <li><a href="#all">Anything</a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body" style="height:365px;overflow-y: auto;">
            	@include('partials.admin_controls.privacy_switcher')
					<ul class="media-list">
                        <li class="media">
                            <div class="media-body messages" v-el:msgs>
                                <div class="media" v-for="msg in messages | orderBy 'id' | filterBy mesej">
                                    <a class="pull-left" href="#">
                                        <img class="media-object img-circle " src="https://image.winudf.com/1119/027c14dd48004c8a/icon=30x.png">
                                    </a>
                                    <div class="media-body" v-bind:class="messageClass(msg)">
                                        <a href="javascript:void(0)" v-on:click="answer(msg)">
		                                    @{{ msg.author.name }}:
		                                </a>
		                                <span>@{{ msg.message }}</span> @include('partials.admin_controls.delete_msg_btn')
                                        <br>
                                       <small class="text-muted">Alex Deo | 23rd June at 5:00pm</small>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
            </div>
            <div class="panel-footer">
            	<div class="input-group _msg">
                    <input type="text" class="form-control" placeholder="Enter Message" v-model="newMessage" v-on:keydown.enter="sendMessage($event)" v-el:input>
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button" v-on:click="sendMessage($event)">SEND</button>
                    </span>
                </div>
            </div>
        </div>
    	</div>

    	<div class="col-md-3">
	          <div class="panel panel-primary">
	            <div class="panel-heading">
	               ROOM
	            </div>
	            <div class="panel-body" style="height:455px;overflow-y: auto;">
	            @include('partials.admin_controls.create_room_btn')
	                <ul class="media-list" id="rooms">
	                    <li class="media" v-for="_room in rooms">
	                        <div class="media-body">
	                            <div class="media">
	                                <a class="pull-left" href="javascript:void(0)" v-bind:class="{'active' : _room.id == room}" v-on:click="changeRoom(_room.id)">
	                                	<img class="media-object img-circle" style="max-height:40px;" src="http://www.aanem.org/App_Themes/AanemNew/images/users.png">
	                                </a>
	                                @include('partials.admin_controls.remove_room_btn')
	                                <div class="media-body">
	                                    <h5><a href="javascript:void(0)" v-bind:class="{'active' : _room.id == room}" v-on:click="changeRoom(_room.id)">
                            			@{{ _room.title }}
                            			</a></h5>
	                                   <small class="text-muted">Active From 3 hours</small>
	                                </div>
	                            </div>

	                        </div>
	                    </li>
	                </ul>
	                </div>
	            </div>
	    </div>
        </div>
       

        <div class="notifications">
            <div class="alert alert-success fade in" role="alert" v-for="noteMsg in notifications ">
                <button type="button" class="close fout" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <p><b>@{{ noteMsg.author.name }}</b></p>
                <p>@{{ noteMsg.message }}</p>

                <a href="javascript:void(0)"
                   class="fout"
                   v-on:click="changeRoom(noteMsg.room_id)"
                   v-show="noteMsg.room_id">

                    Enter room
                </a>

            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create \ edit room</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('rooms.store') }}" id='room' v-on:submit.prevent>
                            <label for="title">Name of room</label>
                            <input type="text" id="title" name="title" class="form-control" v-el:room>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="createRoom()" form="room">Save</button>
                    </div>
                </div>
            </div>
        </div>



        <br><br>



    </div>
