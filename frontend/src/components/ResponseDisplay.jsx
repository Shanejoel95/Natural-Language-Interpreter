import React from 'react'

const ResponseDisplay = ({ response, error }) => {
  if (error) {
    return (
      <div className="response-display error">
        <h3>Error</h3>
        <p>{error}</p>
      </div>
    )
  }
  if (!response) return null

  return (
    <div className="response-display">
      <h3>Response</h3>
      <div className="json-display">
        <pre>{JSON.stringify(response, null, 2)}</pre>
      </div>
    </div>
  )
}

export default ResponseDisplay


