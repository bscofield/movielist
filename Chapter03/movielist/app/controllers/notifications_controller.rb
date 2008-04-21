class NotificationsController < ApplicationController
  before_filter :login_required
  
  def index
    @releases = current_user.releases
  end
end
