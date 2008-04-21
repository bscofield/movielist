class InterestsController < ApplicationController
  before_filter :login_required
  
  def index
    @interests = current_user.interests
  end
  
  def create
    current_user.interests.create(params[:interest])
    flash[:notice] = 'You have added an interest in the specified movie'

    redirect_to user_interests_path
  end
  
  def destroy
    interest = current_user.interests.find(params[:id])
    interest.destroy

    redirect_to user_interests_path
  end
end
