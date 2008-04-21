class Image < ActiveRecord::Base
  belongs_to :record, :polymorphic => true
  
  has_attachment :content_type => :image, 
    :storage => :file_system, 
    :path_prefix => 'public/records'
  
  validates_as_attachment
end
