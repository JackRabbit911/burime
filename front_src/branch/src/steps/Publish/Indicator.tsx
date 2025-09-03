import { useList, useUnit } from "effector-react"
import type React from "react"
import { $readyProgress, $recommendations } from "../../store/validation"

const Indicator = () => {
  const recommendations = useList($recommendations, ({ title, weight }) => (
    <li className={weight === 1 ? 'text-error' : 'text-base-content'}>
      {title}
    </li>
  ))

  const progress = useUnit($readyProgress)
  const style: React.CSSProperties & { '--value': string } = {
    '--value': `${progress}`,
  }

  return (
    <div className="mt-3">
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
      <ul className="list-disc mt-4">
        {recommendations}
      </ul>
    </div>
  )

}

export default Indicator
