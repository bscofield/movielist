class Interest < ActiveRecord::Base
  belongs_to :user
  belongs_to :movie
  
  validates_presence_of :user_id, :movie_id
  
  def movie_title
    movie.title
  end

  def movie_title=(title)
    self.movie = Movie.find_by_title(title)
  end
end
