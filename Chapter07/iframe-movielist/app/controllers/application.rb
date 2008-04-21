# Filters added to this controller apply to all controllers in the application.
# Likewise, all the methods added will be available for all controllers.

class ApplicationController < ActionController::Base
  before_filter :load_facebook_session
  before_filter :detect_iphone_request
  include AuthenticatedSystem

  helper :all # include all helpers, all the time

  # See ActionController::RequestForgeryProtection for details
  # Uncomment the :secret if you're not using the cookie session store
  protect_from_forgery # :secret => '51c6473c18afddc1f930646e39d01b86'
  
  protected
  def detect_iphone_request
    request.format = :iphone if iphone_request?
  end
  
  def iphone_request?
    request.env["HTTP_USER_AGENT"] && 
      request.env["HTTP_USER_AGENT"][/(Mobile\/.+Safari)/]
  end
  
  def require_admin
    access_denied unless logged_in? && current_user.administrator?
  end

  def load_facebook_session
    if fbsession.ready?
      self.current_user = User.find_by_facebook_id(fbsession.session_user_id) || :false
    end 

    true
  end
end
