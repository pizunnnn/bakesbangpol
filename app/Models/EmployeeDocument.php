class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'file'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}