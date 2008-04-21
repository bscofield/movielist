ActionController::Routing::Routes.draw do |map|
  map.resources :comments
  map.resource  :session
  map.resource  :user, :has_many => [:interests, :notifications]
  map.connect   'user/:user_id/notifications', :controller => 'notifications', :action => 'index'
  map.connect   'user/:user_id/notifications.:format', :controller => 'notifications', :action => 'index'

  map.resources :releases
  map.resources :people
  map.resources :movies, :has_many => :comments
  map.resources :users
  
  map.root :controller => 'releases', :action => 'index'
end
