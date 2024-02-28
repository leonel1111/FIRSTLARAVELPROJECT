@include('components.header')

    <div class="container py-md-5 container--narrow">
      <div class="d-flex justify-content-between">
        {{-- will show the title of the post based on the id of the URL which is contained in the postcontroller/showsinglepost function --}}
        <h2>{{$post->title}}</h2>
        @can('update',$post)
        <span class="pt-2">
          <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
          <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
          </form>
        </span>
        @endcan
      </div>

      <p class="text-muted small mb-4">
        <a href="#"><img class="avatar-tiny" src="https://gravatar.com/avatar/f64fc44c03a8a7eb1d52502950879659?s=128" /></a>
        Posted by <a href="#">{{$post->person->username}}</a> on {{$post->created_at->format('n/j/y')}}
      </p>

      <div class="body-content">
        {{-- overiders laravel protection for rendering malicious code inserted by the user --}}
        {!!$post->body!!}
      </div>
    </div>

@include('components.footer')