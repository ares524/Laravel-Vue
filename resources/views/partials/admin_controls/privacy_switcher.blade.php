{{--@if(Auth::user()->isAdmin())--}}

    <a href="javascript:void(0)" v-on:click="showPrivate()" v-show="!showPrivateMessages">
        <i class="fa fa-eye text-success"></i>
        Show private messages
    </a>
    <a href="javascript:void(0)" v-on:click="hidePrivate()" v-show="showPrivateMessages">
        <i class="fa fa-eye-slash text-danger"></i>
        Hide private messages
    </a>

{{--@endif--}}