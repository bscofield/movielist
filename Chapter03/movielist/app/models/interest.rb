class Interest < ActiveRecord::Base
  belongs_to :user
  belongs_to :movie
  
  validates_presence_of :user_id, :movie_id
end
