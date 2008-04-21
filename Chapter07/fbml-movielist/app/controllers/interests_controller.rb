class InterestsController < ApplicationController
  def create
    movie_id = params[:movie_id]
    Interest.create({
      :movie_id => movie_id, 
      :facebook_id => fbsession.session_user_id
    })
    update_profile
    flash[:notice] = 'You have registered your interest in this movie'

    redirect_to movie_path(movie_id)
  end
  
  def destroy
    interest = Interest.find(params[:id])
    movie_id = interest.movie_id
    interest.destroy
    update_profile
    
    flash[:notice] = 'You have removed your interest in this movie'

    redirect_to movie_path(movie_id)
  end

  private
  def update_profile
    interests = Interest.find_all_by_facebook_id(
      fbsession.session_user_id
    ).map(&:movie_id)
    movies = Movie.find(:all).select { |movie| 
      interests.include?(movie.id) 
    }
    
    markup    = render_to_string({
      :partial => 'interests/profile', 
      :locals => {
        :uid => fbsession.session_user_id,
        :movies => movies
      }
    })
    
    fbsession.profile_setFBML({ 
      :markup => markup, 
      :uid => fbsession.session_user_id 
    })
  end
end
