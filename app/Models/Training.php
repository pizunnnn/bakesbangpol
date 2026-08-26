class Training extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'organizer',
        'date',
        'certificate'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}