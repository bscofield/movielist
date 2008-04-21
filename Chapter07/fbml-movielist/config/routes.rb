ActionController::Routing::Routes.draw do |map|
  map.releases 'releases', :controller => 'releases', :action => 'index'

  map.with_options :controller => 'movies' do |m|
    m.movies   'movies',     :action => 'index'
    m.movie    'movies/:id', :action => 'show'
  end
  
  map.with_options :controller => 'interests' do |i|
    i.create_interest  'movies/:movie_id/interests', :action => 'create'
    i.destroy_interest 'interests/:id/destroy',      :action => 'destroy'
  end

  map.root :controller => 'releases', :action => 'index'
end
