import React, { useState } from 'react'

const DateForm = ({ onSubmit, loading }) => {
  const [query, setQuery] = useState('')
  const [type, setType] = useState('date')

  const handleSubmit = (e) => {
    e.preventDefault()
    if (query.trim()) {
      onSubmit(query, type)
      setQuery('')
    }
  }

  return (
    <div className="date-form">
      <h2>Natural Language Interpreter</h2>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="query">Enter your query:</label>
          <input
            id="query"
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder='e.g., "next Tuesday" or "three weeks from now"'
            disabled={loading}
            className="form-input"
          />
        </div>

        <div className="form-group">
          <label htmlFor="type">Query Type:</label>
          <select
            id="type"
            value={type}
            onChange={(e) => setType(e.target.value)}
            disabled={loading}
            className="form-select"
          >
            <option value="date">Date Interpretation</option>
            <option value="product">Product Description</option>
          </select>
        </div>

        <button type="submit" disabled={loading || !query.trim()} className="submit-btn">
          {loading ? 'Processing...' : 'Interpret'}
        </button>
      </form>
    </div>
  )
}

export default DateForm


