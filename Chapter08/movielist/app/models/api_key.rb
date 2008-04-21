require 'digest/sha1'
class ApiKey < ActiveRecord::Base
  THRESHOLD = 50 # allow fifty uses per day per key
  before_validation_on_create :generate_identifier

  belongs_to :user
  has_many :key_accesses, :dependent => :destroy

  validates_presence_of :user_id, :identifier
  validates_uniqueness_of :identifier

  def available?
    uses = self.key_accesses.count(:id, 
      :conditions => [
        'used_at BETWEEN ? AND ?', 
        Date.today.beginning_of_day, 
        Date.today.end_of_day
      ]
    ) 
    allowed = uses < THRESHOLD
  end

  def record_usage
    self.key_accesses.create
  end

  private
  def generate_identifier
    seed = "#{self.class.count}--#{self.user_id}--#{Time.now.to_i}"
    self.identifier = Digest::SHA1.hexdigest(seed)
  end
end