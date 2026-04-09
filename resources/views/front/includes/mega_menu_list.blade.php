    <div class="container">
      <div class="row">
      
        <div class="order-2 order-md-2 order-lg-1 col-12 col-md-12 col-lg-8">
            <div class="row py-4 mb-3 mb-lg-0">
                <h6 class="ps-4 pb-2 ms-1">Popular Games</h6> 
                <div class="col-12 col-md-12 col-lg-6">
                  <div class="list-group list-group-flush">
                    @foreach($category->activegames()->take(12)->get() as $key=>$game)
                        @if($key==6)
                                  </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                          <div class="list-group list-group-flush">
                        @endif

                    <a href="{{route('front.product',$game->slug)}}" class="list-group-item list-group-item-action">
                        <img src="{!! Helpers::image($game->photo, 'games/') !!}" style="width: 30px;">
                        {{$game->name}}</a>
                    @endforeach
                  </div>
                </div> 
            </div>
        </div>
        

       {{--  <div class="order-1 order-md-1 order-lg-2 col-12 col-md-12 col-lg-4 py-4 megamenu-3list-bg">
            <div>
                <div class="form-icon right">
                    <input type="text" class="form-control form-control-icon" id="iconrightInput" data-search-in="gamelisttosearch" 
                        placeholder="Search Game">
                    <i class="ri-search-line"></i>
                </div>
            </div>
          <div class="list-group list-group-flush mt-1" data-simplebar data-simplebar-auto-hide="false" style="max-height: 260px;" data-search-list="gamelisttosearch">
            @foreach($category->activegames()->get() as $key=>$game)
            <a href="{{route('front.product',$game->slug)}}" class="list-group-item list-group-item-action">{{$game->name}}</a>
            @endforeach            
          </div>
        </div> --}}


        <div class="order-1 order-md-1 order-lg-2 col-12 col-md-12 col-lg-4 py-4 megamenu-3list-bg">
            <div class="form-icon right">
                <input type="text" class="form-control form-control-icon gameSearchInput" data-search-in="game-list-{{$category->slug}}" placeholder="Search Game">
                <i class="ri-search-line"></i>
            </div>
            <div class="list-group list-group-flush mt-1" data-simplebar data-simplebar-auto-hide="false" style="max-height: 260px;" id="game-list-{{$category->slug}}">
                @foreach($category->activegames as $game)
                    <a href="{{ route('front.product', $game->slug) }}" class="list-group-item list-group-item-action">{{ $game->name }}</a>
                @endforeach
            </div>
        </div>




      </div>
    </div>