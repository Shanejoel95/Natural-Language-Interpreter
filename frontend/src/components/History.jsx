import React from 'react'

const History = ({ history }) => {
  if (!history || history.length === 0) {
    return (
      <div className="history">
        <h3>History</h3>
        <p>No previous requests</p>
      </div>
    )
  }

  return (
    <div className="history">
      <h3>History</h3>
      <div className="history-list">
        {history.map((item) => (
          <div key={item.id} className="history-item">
            <div className="history-header">
              <span className="history-query">{item.user_query}</span>
              <span className="history-date">{new Date(item.created_at).toLocaleString()}</span>
            </div>
            <div className="history-response">
              <pre>{JSON.stringify(item.json_response, null, 2)}</pre>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}

export default History


