class SalaryHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'salary',
        'effective_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}