class NotificationsController < ApplicationController
  before_filter :require_login_or_user
  
  def index
    @releases = @user.releases(true)
    respond_to do |format|
      format.html
      format.iphone
      format.js { render :template => 'releases/index' }
    end
  end
  
  private 
  def require_login_or_user
    if params[:user_id]
      @user = User.find_by_id(params[:user_id]) 
    elsif logged_in?
      @user = current_user
    else
      access_denied
    end
  end
end
