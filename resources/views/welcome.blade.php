@extends('layout.app')
@section('content')
    <div class="container-fluid" id="chat">
        <div class="row">
            <div class="col-md-1"></div>

            <div class="col-md-7">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>LaraChat</b>
                    </div>

                    <div class="input-group" id="adv-search" style="width:100%">
                        <input type="hidden" name="search_param" value="all" id="search_param">
                        <input v-model="mesej" type="text" class="form-control" placeholder="Search..." />
                        <div class="input-group-btn">
                            <div class="btn-group" role="group">
                                <div class="dropdown dropdown-lg">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        <form class="form-horizontal" role="form">
                                          <div class="form-group">
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <p><b>Filter By Seen</b></p>
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox1" type="checkbox">
                                                        <label for="checkbox1">Today</label>
                                                    </div>
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox2" type="checkbox" >
                                                        <label for="checkbox2">This Week</label>
                                                    </div>
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox3" type="checkbox">
                                                        <label for="checkbox3">Last Week</label>
                                                    </div>
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox4" type="checkbox">
                                                        <label for="checkbox4">This Month</label>
                                                    </div>
                                                    <br>
                                                    <p><b>Filter By Photo</b></p>
                                                    <div class="checkbox checkbox-primary checkbox-inline">
                                                        <input v-model="showphoto" type="checkbox" id="inlineCheckbox1" checked>
                                                        <label for="inlineCheckbox1"> Show Photo </label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                          </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel-body" style="height:365px;">
                        {{--@include('partials.admin_controls.privacy_switcher')--}}
                        <ul class="media-list">
                            <li class="media">
                                <div class="media-body messages" v-el:msgs>
                                    <div class="media message" v-for="msg in messages | orderBy 'id' | filterBy mesej">
                                        <a class="pull-left" href="#">
                                            <img v-if="showphoto" class="media-object img-circle " src="img/icon=30x.png">
                                        </a>
                                        @include('partials.admin_controls.delete_msg_btn')
                                        <div class="media-body" v-bind:class="messageClass(msg)">
                                            <a href="javascript:void(0)" v-on:click="answer(msg)">
                                                @{{ msg.author.name }}
                                            </a>
                                            <span>@{{ msg.message | reverse}}</span>
                                            <br>
                                            <small class="text-muted">@{{ nowtime(msg.created_at) }}</small>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <div class="input-group _msg">
                            <input type="text" class="form-control" placeholder="Enter Message" v-model="newMessage"
                                   v-on:keydown.enter="sendMessage($event)" v-el:input>
                            <span class="input-group-btn">
                        <button class="btn btn-info" type="button" v-on:click="sendMessage($event)">SEND</button>
                    </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">Friend</div>
                    <div class="panel-body" style="height:455px;overflow-y: auto;">
                        @include('partials.admin_controls.create_room_btn')
                        <ul class="media-list" id="rooms">
                            <li class="media" v-for="_room in rooms">
                                <div class="media-body">
                                    <div class="media">
                                        <a class="pull-left" href="javascript:void(0)"
                                           v-bind:class="{'active' : _room.id == room}"
                                           v-on:click="changeRoom(_room.id)">
                                            <img class="media-object img-circle" style="max-height:40px;"
                                                 src="img/users.png">
                                        </a>
                                        @include('partials.admin_controls.remove_room_btn')
                                        <div class="media-body">
                                            <h5><a href="javascript:void(0)"
                                                   v-bind:class="{'active' : _room.id == room}"
                                                   v-on:click="changeRoom(_room.id)">
                                                    @{{ _room.title }}
                                                </a></h5>
                                            <small class="text-muted">Active From @{{  nowtime(_room.created_at) }}</small>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
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
                        <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="createRoom()"
                                form="room">Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
    </div>
@endsection
