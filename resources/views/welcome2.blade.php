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

        <header class="row">
            <div class="col-xs-9">
                <h1>LaraChat</h1>
            </div>
            <div class="col-xs-3">
                <br>
                <span>Welcome, <b>{{ Auth::user()->name }}</b></span>
                <a href="/auth/logout" class="pull-right"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </header>

        <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <div class="input-group">
                <input v-model="mesej" type="text" class="form-control" placeholder="search..." />
            
                <div class="input-group-btn">
                    <div class="btn-group" role="group">
                        <div class="dropdown dropdown-lg">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <form class="form-horizontal" role="form">
                                  <div class="form-group">
                                    <label for="filter">Filter by</label>
                                    <select class="form-control">
                                        <option value="0" selected>All Snippets</option>
                                        <option value="1">Featured</option>
                                        <option value="2">Most popular</option>
                                        <option value="3">Top rated</option>
                                        <option value="4">Most commented</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="contain">Author</label>
                                    <input class="form-control" type="text" />
                                  </div>
                                  <div class="form-group">
                                    <label for="contain">Contains the words</label>
                                    <input class="form-control" type="text" />
                                  </div>
                                  <button type="submit" class="btn btn-primary"><span aria-hidden="true">Search</span></button>
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary"><span aria-hidden="true">Search</span></button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-3">

                @include('partials.admin_controls.create_room_btn')

                <ul class="list-group" id="rooms">
                    <li class="list-group-item" v-for="_room in rooms">
                        <a href="javascript:void(0)"
                           v-bind:class="{'active' : _room.id == room}"
                           v-on:click="changeRoom(_room.id)">
                            @{{ _room.title }}
                        </a>

                    @include('partials.admin_controls.remove_room_btn')

                    </li>
                </ul>
            </div>

            <div class="col-sm-9">

               @include('partials.admin_controls.privacy_switcher')

                <div>
                    <div class="panel panel-default messages" v-el:msgs>
                        <div v-for="msg in messages | orderBy 'id' | filterBy mesej">
                            <p class="message" v-bind:class="messageClass(msg)">
                                <a href="javascript:void(0)" v-on:click="answer(msg)">
                                    @{{ msg.author.name }}:
                                </a>
                                <span>@{{ msg.message }}</span>

                                @include('partials.admin_controls.delete_msg_btn')

                            </p>
                        </div>
                    </div>

                    <div class="input-group _msg">
                    <input type="text"
                           class="form-control"
                           placeholder="Message"
                           v-model="newMessage"
                           v-on:keydown.enter="sendMessage($event)"
                           v-el:input
                        >
                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" v-on:click="sendMessage($event)">Send</button>
                      </span>
                    </div><!-- /input-group -->

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







    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.7/socket.io.min.js"></script>
    <script src="/js/main.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>



</body>
</html>