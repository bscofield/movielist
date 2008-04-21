# Methods added to this helper will be available to all templates in the application.
module ApplicationHelper
  def admin_link_to(*options)
    link_to(*options) if logged_in? && current_user.administrator?
  end
end
