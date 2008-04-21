class Release < ActiveRecord::Base
  cattr_reader :per_page
  @@per_page = 10

  belongs_to :movie
  
  validates_presence_of :movie_id, :format, :released_on
  
  def to_s
    [self.format, released_on.to_s(:short)].join(' - ')
  end
  
  def self.upcoming(params)
    limit      = params[:limit] || nil
    rel_format = params[:release_format] || 'theater'
    raw_time   = params[:time] || '1 month'
    time       = eval("#{raw_time.sub(/ /, '.')}.from_now")
    Release.find(:all, 
      :include => :movie,
      :limit => limit, 
      :order => 'released_on DESC',
      :conditions => ['format = ? AND released_on BETWEEN ? AND ?', 
        rel_format,
        Date.today,
        time
      ]
    )
  end
end
