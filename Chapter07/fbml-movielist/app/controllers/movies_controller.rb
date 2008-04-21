class MoviesController < ApplicationController
  def index
    @movies = Movie.find(:all)
  end

  def show
    @movie = Movie.find(params[:id])
    @interest = Interest.find_by_movie_id_and_facebook_id(
      @movie.id, 
      fbsession.session_user_id
    )
    @interested_friends = Interest.find(:all, 
      :conditions => ["movie_id = ? AND facebook_id IN (?)", 
        @movie.id,
        fbsession.friends_get.uid_list
      ]
    )
  end
end
