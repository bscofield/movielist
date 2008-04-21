# Filters added to this controller apply to all controllers in the application.
# Likewise, all the methods added will be available for all controllers.

class ApplicationController < ActionController::Base
  before_filter :validate_api_key
  before_filter :detect_iphone_request
  include AuthenticatedSystem

  helper :all # include all helpers, all the time

  # See ActionController::RequestForgeryProtection for details
  # Uncomment the :secret if you're not using the cookie session store
  protect_from_forgery # :secret => '51c6473c18afddc1f930646e39d01b86'
  
  protected
  def validate_api_key	
    if request.format == 'application/xml'
      key = ApiKey.find_by_identifier(params[:api_key])
      return false unless (key && key.available?)
      key.record_usage
    end
    true
  end

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
end
