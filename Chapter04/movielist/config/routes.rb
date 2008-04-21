ActionController::Routing::Routes.draw do |map|
  map.resource  :session
  map.resource  :user, :has_many => [:interests, :notifications]
  map.connect   'user/:user_id/notifications', :controller => 'notifications', :action => 'index'
  map.connect   'user/:user_id/notifications.:format', :controller => 'notifications', :action => 'index'

  map.resources :releases
  map.resources :people
  map.resources :movies
  map.resources :users
  
  map.root :controller => 'movies', :action => 'index'
end
