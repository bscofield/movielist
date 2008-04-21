class AuditorObserver < ActiveRecord::Observer
  observe :user, :movie, :release

  def after_create(record)
    record.audits.create :event => 'created'
  end
end
