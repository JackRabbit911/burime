import { getAlerts, readyProgress } from "App/utils"
import type React from "react"
import { useFormContext } from "react-hook-form"

const Indicator = () => {
  const { watch } = useFormContext()
  const values = watch()
  const alerts = getAlerts(values)
  const progress = readyProgress(values)
  const style: React.CSSProperties & { '--value': string } = {
    '--value': `${progress}`,
  }

  return (
    <div className="mt-4">
      <div className="text-center">
        <div
          className="radial-progress text-primary dark:text-info"
          style={style}
          aria-valuenow={progress}
          role="progressbar"
        >
          {progress}%
        </div>
      </div>
      {alerts.length > 0 ? 
      <ul className="list-disc mt-4">
        {alerts.map((item, key) => <li key={key}>{item}</li>)}
      </ul>:
      <div className="w-full text-center text-success mt-3">
        Yes!<br />Your project is ready to publish!
      </div>}
      
    </div>
  )

}

export default Indicator
