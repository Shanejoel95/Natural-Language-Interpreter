import React, { useState, useEffect } from 'react'
import DateForm from './components/DateForm'
import ResponseDisplay from './components/ResponseDisplay'
import History from './components/History'
import { interpretQuery, getHistory } from './services/api'
import './App.css'

function App() {
  const [loading, setLoading] = useState(false)
  const [response, setResponse] = useState(null)
  const [error, setError] = useState(null)
  const [history, setHistory] = useState([])

  useEffect(() => {
    loadHistory()
  }, [])

  const loadHistory = async () => {
    try {
      const result = await getHistory()
      setHistory(result.data || [])
    } catch (err) {
      console.error('Failed to load history:', err)
    }
  }

  const handleSubmit = async (query, type) => {
    setLoading(true)
    setError(null)
    setResponse(null)
    try {
      const result = await interpretQuery(query, type)
      setResponse(result.data)
      await loadHistory()
    } catch (err) {
      setError(err.error || 'An error occurred')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="app">
      <header className="app-header">
        <h1>Natural Language Date Interpreter</h1>
      </header>
      <main className="app-main">
        <div className="left-panel">
          <DateForm onSubmit={handleSubmit} loading={loading} />
          <ResponseDisplay response={response} error={error} />
        </div>
        <div className="right-panel">
          <History history={history} />
        </div>
      </main>
    </div>
  )
}

export default App


