class RankHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'rank',
        'start_date',
        'end_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}