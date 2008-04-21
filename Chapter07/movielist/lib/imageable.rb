module Imageable
  def self.included(base)
    base.class_eval do
      has_one :image, :dependent => :destroy, :as => :record
    end
  end
  
  def uploaded_data=(data)
    unless data.blank?
      image.destroy if image
      self.reload
      create_image :uploaded_data => data
    end
  end
end