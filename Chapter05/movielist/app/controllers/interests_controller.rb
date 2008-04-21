class InterestsController < ApplicationController
  before_filter :login_required
  
  def index
    @interests = current_user.interests
    
    respond_to do |format|
      format.html
      format.json { render :json => @interests.to_json(
        :methods => [:movie_title]
      ) }
    end
  end
  
  def create
    interest = current_user.interests.create(params[:interest])
    flash[:notice] = 'You have added an interest in the specified movie'

    respond_to do |format|
      format.html { redirect_to user_interests_path }
      format.json { 
        status_code = interest.new_record? ? 422 : 201
        render :json => current_user.interests.reload.to_json(
          :methods => [:movie_title]
        ), :status => status_code }
    end
  end
  
  def destroy
    interest = current_user.interests.find(params[:id])
    interest.destroy

    respond_to do |format|
      format.html { redirect_to user_interests_path }
      format.json { 
        render :json => current_user.interests.reload.to_json(
          :methods => [:movie_title]
        )
      }
    end
  end
end
